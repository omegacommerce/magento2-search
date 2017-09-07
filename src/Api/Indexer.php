<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Api;

use OmegaCommerce\Api\Interfaces\EntityInterface;
use OmegaCommerce\Api\Interfaces\DbInterface;

class Indexer
{
    const OMEGA_SYNC_STATUS_TABLE = 'omega_index_status';

    /**
     * @var EntityInterface[]
     */
    protected $entities = array();

    public function __construct(
        Batch $batch,
        Client $client,
        DbInterface $db
    ) {
        $this->batch = $batch;
        $this->client = $client;
        $this->db = $db;
        $this->statusTable = $db->getTableName(self::OMEGA_SYNC_STATUS_TABLE);
    }

    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function registerEntity(EntityInterface $entity) {
        $this->entities[] = $entity;
    }

    /**
     * @return EntityInterface[]
     */
    public function getEntities() {
        return $this->entities;
    }

    /**
     * @return void
     */
    public function clean() {
        $this->client->request(Client::METHOD_DELETE, "/entity/all", array(), array());
        $this->cleanOnlyStatusTable();
    }

    /**
     * @return void
     */
    public function cleanOnlyStatusTable() {
        $sql = "DELETE FROM {$this->statusTable}";
        $this->db->query($sql);
    }

    /**
     * @param EntityInterface $entity
     * @return int
     */
    public function reindexQueueLength(EntityInterface $entity) {
        $sql = "SELECT count(distinct entity_id) FROM {$this->statusTable}
           WHERE is_reindexed = 0 AND entity_type='{$entity->getType()}'";

        $size = $this->db->fetchCol($sql);
        $size = $size[0];
        return $size;
    }

    /**
     * @param EntityInterface $entity
     * @param int[] $ids
     * @return void
     */
    public function reindexEntityByIDs(EntityInterface $entity, $ids) {
        $this->batch->startBatch("save", $entity->getType());
        $elements = $entity->getEntityElementsByIds($ids);
        foreach($elements as $element) {
            $this->batch->addEntityElement($element);
        }
        $this->batch->finishBatch();

        $ids[] = -1;
        $idsStr = implode(",", $ids);
        $sql = "UPDATE {$this->statusTable} SET is_reindexed = 1 WHERE entity_type='{$entity->getType()}' AND entity_id IN($idsStr)";
        $this->db->query($sql);
    }

    /**
     * @param EntityInterface $entity
     * @param int $limit
     * @return void
     */
    public function reindexEntity(EntityInterface $entity, $limit) {
        $ids = $this->getChangedIDs($entity, $limit);
        $this->reindexEntityByIDs($entity, $ids);
    }

    /**
     * @param EntityInterface $entity
     */
    public function refreshStatus(EntityInterface $entity){
        //i.e. wp_blogs table may not exist, but one blog exists
        if (!$entity->getMainTable()) {
            return;
        }
        $this->db->query("SET SESSION group_concat_max_len = 4294967295");

        $tables = $entity->getLinkedTables();
        $tables[] = $entity->getMainTable();
        /** @var Entity\Table $table */
        foreach($tables as $table) {
            $where = $table->getWhere();
            if ($where == "") {
                $where = "1 = 1";
            }

            $leftJoins = implode("\n", $table->getLeftJoins());
            $sql = "
            REPLACE INTO {$this->statusTable}(entity_id, entity_type, table_name, updated_at, hash, old_hash)
            SELECT
                main.{$table->getIDField()} as entity_id,
                '{$entity->getType()}' as entity_type,
                '{$table->getName()}' as `table_name`,
                NOW() as updated_at,
                (GROUP_CONCAT((CONCAT(".implode(",", $table->getFields()).")) SEPARATOR '|')) as hash,
                status.hash as old_hash
            FROM {$table->getName()} main
            $leftJoins
            LEFT JOIN {$this->statusTable} status ON
                main.{$table->getIDField()} = status.entity_id
                AND status.entity_type='{$entity->getType()}'
                AND status.table_name='{$table->getName()}'
            WHERE $where
            GROUP BY main.{$table->getIDField()}
            HAVING hash != old_hash OR ISNULL(old_hash)
            ";

            $this->db->query($sql);
        }
    }
    /**
     * @param EntityInterface $entity
     * @param int $limit
     * @return array
     */
    protected function getChangedIDs(EntityInterface $entity, $limit){
        $sql = "
        SELECT entity_id FROM {$this->statusTable}
        WHERE is_reindexed = 0 AND entity_type = '{$entity->getType()}'
        GROUP BY entity_id
        LIMIT {$limit} ";
        $ids = $this->db->fetchCol($sql);
        return $ids;
    }

    /**
     * @param EntityInterface $entity
     * @param int $limit
     * @return int
     */
    public function removeEntity(EntityInterface $entity, $limit) {
        $ids = $this->getRemovedIDs($entity, $limit);
        $count = count($ids);
        if (!$count) {
            return 0;
        }

        $this->batch->startBatch("delete", $entity->getType());
        foreach($ids as $id) {
            $this->batch->addItem($id);
        }
        $this->batch->finishBatch();


        $ids[] = -1;
        $idsStr = implode(",", $ids);
        $sql = "DELETE FROM {$this->statusTable} WHERE entity_type='{$entity->getType()}' AND entity_id IN($idsStr)";
        $this->db->query($sql);
        return $count;
    }

    /**
     * @param EntityInterface $entity
     * @param int $limit
     * @return array
     */
    protected function getRemovedIDs(EntityInterface $entity, $limit){
        //i.e. wp_blogs table may not exist, but one blog exists
        if (!$entity->getMainTable()) {
            return array();
        }
        $table = $entity->getMainTable();
        $sql = "
        SELECT status.entity_id FROM {$this->statusTable} status
        LEFT OUTER JOIN {$table->getName()} main
        ON status.entity_id = main.{$table->getIDField()} AND {$table->getWhere()}
        WHERE main.{$table->getIDField()} IS NULL
        AND status.entity_type = '{$entity->getType()}'
        AND status.table_name = '{$table->getName()}'
        LIMIT {$limit} ";
        $ids = $this->db->fetchCol($sql);
        return $ids;
    }
}