<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type', 191); // Limit tokenable_type to 191 characters
            $table->unsignedBigInteger('tokenable_id'); // Ensure tokenable_id is an unsigned big integer
            $table->string('name');
            $table->string('token', 64);
            $table->text('abilities');
            $table->timestamp('last_used_at');
            $table->timestamps();

            // Add the index for tokenable_type and tokenable_id
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
}
