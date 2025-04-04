<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToPhasesTable extends Migration
{
    public function up()
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->integer('order')->default(0); // Ajoute une colonne "order" avec une valeur par défaut
        });
    }

    public function down()
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->dropColumn('order'); // Supprime la colonne "order" en cas de rollback
        });
    }
}

