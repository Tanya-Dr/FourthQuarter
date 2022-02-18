<?php
abstract class AbstractFactory{
    abstract public function getDBConnection() : DBConnection; 
    abstract public function getDBRecord() : DBRecord;
    abstract public function getDBQueryBuilder() : DBQueryBuilder;
}

class MySQLFactory extends AbstractFactory{
    public function getDBConnection() : DBConnection{
        return new MySQLConnection();
    }
    public function getDBRecord() : DBRecord{
        return new MySQLRecord();
    }
    public function getDBQueryBuilder() : DBQueryBuilder{
        return new MySQLQueryBuilder();
    }
}

class PostgreSQLFactory extends AbstractFactory{
    public function getDBConnection() : DBConnection{
        return new PostgreSQLConnection();
    }
    public function getDBRecord() : DBRecord{
        return new PostgreSQLRecord();
    }
    public function getDBQueryBuilder() : DBQueryBuilder{
        return new PostgreSQLQueryBuilder();
    }
}

class OracleFactory extends AbstractFactory{
    public function getDBConnection() : DBConnection{
        return new OracleConnection();
    }
    public function getDBRecord() : DBRecord{
        return new OracleRecord();
    }
    public function getDBQueryBuilder() : DBQueryBuilder{
        return new OracleQueryBuilder();
    }
}

interface DBConnection{
    public function createConnection() : string;
}
class MySQLConnection implements DBConnection{
    public function createConnection() : string{
        return "MySQL database was successfully connected.";
    }
}
class PostgreSQLConnection implements DBConnection{
    public function createConnection() : string{
        return "PostgreSQL database was successfully connected.";
    }
}
class OracleConnection implements DBConnection{
    public function createConnection() : string{
        return "Oracle database was successfully connected.";
    }
}

interface DBRecord{
    public function makeRecord() : string;
}
class MySQLRecord implements DBRecord{
    public function makeRecord() : string{
        return "MySQL database was successfully recorded.";
    }
}
class PostgreSQLRecord implements DBRecord{
    public function makeRecord() : string{
        return "PostgreSQL database was successfully recorded.";
    }
}
class OracleRecord implements DBRecord{
    public function makeRecord() : string{
        return "Oracle database was successfully recorded.";
    }
}

interface DBQueryBuilder{
    public function makeQuery() : string;
}
class MySQLQueryBuilder implements DBQueryBuilder{
    public function makeQuery() : string{
        return "MySQL query builder.";
    }
}
class PostgreSQLQueryBuilder implements DBQueryBuilder{
    public function makeQuery() : string{
        return "PostgreSQL query builder.";
    }
}
class OracleQueryBuilder implements DBQueryBuilder{
    public function makeQuery() : string{
        return "Oracle query builder.";
    }
}

function clientCode(AbstractFactory $factory){
    $connection = $factory->getDBConnection();
    $record = $factory->getDBRecord();
    $query = $factory->getDBQueryBuilder();
    echo $connection->createConnection() . "\n" . $record->makeRecord() . "\n" . $query->makeQuery();
}

echo "Client: Testing client code with the first factory type (MySQL):\n";
clientCode(new MySQLFactory());

echo "\n";

echo "Client: Testing the same client code with the second factory type (PostgreSQL):\n";
clientCode(new PostgreSQLFactory());

echo "\n";

echo "Client: Testing the same client code with the second factory type (Oracle):\n";
clientCode(new OracleFactory());