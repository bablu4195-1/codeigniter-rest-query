<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',    
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'name' => [
                'type' => 'VARCHAR',    
                'constraint' => 50,
                'null' => false,
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => 50,
                "null" => false,
                "unique" => true,
            ],
            "profile_image" =>[
               "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true,
            ]
        ]);
        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("employees");


    }

    public function down()
    {
        $this->forge->dropTable("employees");
    }
}
