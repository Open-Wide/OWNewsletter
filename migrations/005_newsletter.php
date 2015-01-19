<?php

/**
 * Create Newsletter content class
 */
class OWNewsletter_005_Newsletter {

    public function up() {
        $migration = new OWMigrationContentClass( );
        $migration->startMigrationOn( 'newsletter' );
        $migration->createIfNotExists();

        $migration->always_available = TRUE;
        $migration->contentobject_name = '<short_title|title>';
        $migration->is_container = TRUE;

        $migration->addAttribute( 'title', array(
            'is_required' => TRUE,
        ) );
        $migration->addAttribute( 'short_title' );
        $migration->addAttribute( 'short_description', array(
            'data_type_string' => 'ezxmltext'
        ) );
        $migration->addAttribute( 'configuration', array(
            'can_translate' => FALSE,
            'data_type_string' => 'ownewsletter',
            'is_searchable' => FALSE
        ) );

        $migration->addToContentClassGroup( 'Newsletter' );
        $migration->end();
    }

    public function down() {
        $migration = new OWMigrationContentClass( );
        $migration->startMigrationOn( 'newsletter' );
        $migration->removeClass();
    }

}
