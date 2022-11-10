<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            // Identifiable
            $table->id();

            // 'belongsTo' relationships
            $table->string('platform');
            $table->string('currency');

            /**
             * 'belongsToMany' account relationships are
             * defined in the payment_orginator and
             * payment_beneficiary intermediate tables.
             */

            // Non-relational attributes

            /**
             * Amount transfered in the currency base unit
             */
            $table->integer('amount');

            /**
             * The ID reference used by the external platform.
             * Banks: e.g. Enumis transaction ID
             * Exchange: Transaction ID?
             * Blockchain: TXID
             */
            $table->string('platformIdentifier');

            /**
             * A reference code shared between all parties.
             * Banks: 'Payment Reference'
             * Exchange: ???
             * Blockchain: null
             */
            $table->string('publicIdentifier');

            /**
             * Datetime of the transaction as
             * recorded by the external platform.
             */
            $table->timestamp('timestamp');

            // Required by all
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
