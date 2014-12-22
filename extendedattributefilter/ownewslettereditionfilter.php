<?php

/**
 * This filter allowed, fetch all lists by a specific status<br>
 * status-variants:<br>
 * ->DRAFT<br>
 * ->PROCESS<br>
 * ->ARCHIVE<br>
 * ->ABORT<br>
 *
 * <code>
 *    fetch('content','list',hash('parent_node_id', 2,
 *           'extended_attribute_filter',
 *           hash( 'id', 'newsletter_edition_filter',
 *                 'params', hash( 'status', 'draft' ) )
 *          ) )
 * </code>
 */
class OWNewsletterEditionFilter {

    /**
     *
     * @param array $parameter
     * @return array
     */
    function createSqlParts( $parameter ) {
        $sqlCond = false;
        $sqlTables = false;
        $sqlColumns = false;

        $status = FALSE;

        if( array_key_exists( 'status', $parameter ) ) {
            $status = $parameter['status'];
        }

        if( $status == 'draft' ) {
            $sql = "SELECT c.edition_contentobject_id FROM ownl_sending c, ezcontentobject e
					WHERE e.id = c.edition_contentobject_id
					AND e.current_version = c.edition_contentobject_version 
					AND c.status = " . OWNewsletterSending::STATUS_DRAFT;
            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sql . ' ) AND ';
            return array( 'tables' => $sqlTables, 'joins' => $sqlCond, 'columns' => $sqlColumns );
        } else if( $status == 'process' ) {
            $sql = "SELECT c.edition_contentobject_id FROM ownl_sending c, ezcontentobject e
                    WHERE e.id = c.edition_contentobject_id
					AND e.current_version = c.edition_contentobject_version
					AND ( c.status = " . OWNewsletterSending::STATUS_WAIT_FOR_PROCESS .
                " OR c.status = " . OWNewsletterSending::STATUS_MAILQUEUE_CREATED .
                " OR c.status = " . OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_STARTED .
                " )";
            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sql . ' ) AND ';
            return array( 'tables' => $sqlTables, 'joins' => $sqlCond, 'columns' => $sqlColumns );
        } else if( $status == 'archive' ) {
            $sql = "SELECT c.edition_contentobject_id FROM ownl_sending c, ezcontentobject e
					WHERE e.id = c.edition_contentobject_id
					AND e.current_version = c.edition_contentobject_version
					AND c.status = " . OWNewsletterSending::STATUS_MAILQUEUE_PROCESS_FINISHED;
            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sql . ' ) AND ';
            return array( 'tables' => $sqlTables, 'joins' => $sqlCond, 'columns' => $sqlColumns );
        } else if( $status == 'abort' ) {

            $sql = "SELECT c.edition_contentobject_id FROM ownl_sending c, ezcontentobject e
					WHERE e.id = c.edition_contentobject_id
					AND e.current_version = c.edition_contentobject_version
					AND c.status = " . OWNewsletterSending::STATUS_ABORT;
            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sql . ' ) AND ';
            return array( 'tables' => $sqlTables, 'joins' => $sqlCond, 'columns' => $sqlColumns );
        } else {
            return array( 'tables' => false, 'joins' => false, 'columns' => false );
        }
    }

}

?>
