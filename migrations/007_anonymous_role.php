<?php

class OWNewsletter_007_AnonymousRole {
    public function up( ) {
        $migration = new OWMigrationRole( );
        $migration->startMigrationOn( 'Anonymous' );
        $migration->addPolicy( 'newsletter', 'configure' );
        $migration->addPolicy( 'newsletter', 'subscribe' );
        $migration->addPolicy( 'newsletter', 'unsubscribe' );
        $migration->end( );
    }
 
    public function down( ) {
        $migration = new OWMigrationRole( );
        $migration->startMigrationOn( 'Anonymous' );
        $migration->removePolicy( 'newsletter', 'configure' );
        $migration->removePolicy( 'newsletter', 'subscribe' );
        $migration->removePolicy( 'newsletter', 'unsubscribe' );
        $migration->end( );
    }

