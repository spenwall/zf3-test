<?php

namespace Blog\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;

class ZendDbSqlRepository implements PostRepositoryInterface
{
    private $db;
    
    /**
     * @param AdapterInterface $db
     */
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllPosts()
    {
        $sql    = new Sql($this->db);
        $select = $sql->select('posts');
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        return $result;
    }

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findPost($id)
    {
    }
}