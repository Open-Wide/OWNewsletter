<?php

/**
* Create Newsletter Edition content class
*/
class OWNewsletter_006_NewsletterEdition {

	public function up() {
		$migration = new OWMigrationContentClass( );
		$migration->startMigrationOn( 'newsletter_edition' );
		$migration->createIfNotExists();

		$migration->always_available = TRUE;
		$migration->contentobject_name = '<short_title|title>';
		$migration->is_container = TRUE;

		$migration->addAttribute( 'title', array(
			'can_translate' => FALSE,
			'is_required' => TRUE
		) );
		$migration->addAttribute( 'short_title' );
		$migration->addAttribute( 'short_description', array(
			'can_translate' => FALSE,
			'data_type_string' => 'ezxmltext' 
		) );
		$migration->addAttribute( 'description', array(
			'can_translate' => FALSE,
			'data_type_string' => 'ezxmltext'
		) );
        $migration->addAttribute( 'configuration', array(
            'can_translate' => FALSE,
            'data_type_string' => 'ownewsletteredition',
            'is_searchable' => FALSE
        ) );

		$migration->addToContentClassGroup( 'Newsletter Editions' );
		$migration->end();
	}

	public function down() {
		$migration = new OWMigrationContentClass( );
		$migration->startMigrationOn( 'newsletter_edition' );
		$migration->removeClass();
	}

}
