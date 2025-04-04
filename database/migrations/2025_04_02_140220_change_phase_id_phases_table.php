<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePhaseIdPhasesTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('phases_id', 'phase_id'); // Renomme la colonne
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('phase_id', 'phases_id'); // Revert en cas de rollback
        });
    }
}

