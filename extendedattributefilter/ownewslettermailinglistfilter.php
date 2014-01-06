<?php
/**
 * This filter allowed, fetch all lists by a specific siteaccess<br>
 * reserved word "current_siteaccess" == take current siteaccess to match<br>
 * param siteaccess : string or array
 *
 * <code>
 *    fetch('content','list',hash('parent_node_id', 2,
 *           'extended_attribute_filter',
 *           hash( 'id', 'newsletter_mailing_list_filter',
 *                 'params', hash( 'siteaccess', 'current_siteaccess' ) )
 *         ) )
 * </code>
 */
class OWNewsletterMailingListFilter
{

    /**
     *
     * @param array $parameter
     * @return array
     */
    function createSqlParts( $parameter )
    {
        $db = eZDB::instance();
        $sqlCond = false;
        $sqlTables = false;
        $sqlColumns = false;

        $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

        $siteAccessArray = array();
        if ( array_key_exists( 'siteaccess', $parameter ) )
        {
            $paramSiteAccess = $parameter['siteaccess'];
            if ( !is_array( $paramSiteAccess ) )
            {
                if ( $paramSiteAccess == 'current_siteaccess' ) {
					$siteAccessArray = array( $currentSiteAccess );
				} else {
					$siteAccessArray = array( $paramSiteAccess );
				}
			}
            else
            {
                foreach ( $paramSiteAccess as $name )
                {
                    if ( $name == 'current_siteaccess' ) {
						$siteAccessArray = array( $currentSiteAccess );
					} else {
						$siteAccessArray = array( $name );
					}
				}
            }
        }

        if ( count( $siteAccessArray ) > 0 )
        {
            $siteaccessSqlStringArray = '';
            foreach ( $siteAccessArray as $siteAccessName )
            {
                $siteaccessSqlStringArray[] = "c.siteaccess_list_string like '%;". $db->escapeString( $siteAccessName ) .";%'";
            }
            $sqlHasSiteAccess = "SELECT c.contentobject_id FROM ownl_mailing_list c, ezcontentobject e
                                 WHERE e.id = c.contentobject_id
                                 AND e.current_version = c.contentobject_attribute_version
                                 AND ( ". implode( ' AND ', $siteaccessSqlStringArray ) ." )";

            $sqlHasSiteAccessResult = array( 0 );
            $result =  $db->arrayQuery( $sqlHasSiteAccess );
            foreach ( $result as $row )
            {
                $sqlHasSiteAccessResult[] = $row[ 'contentobject_id' ];
            }
            $sqlHasSiteAccessImplode = implode( ',', $sqlHasSiteAccessResult );

            $sqlCond .= ' ezcontentobject_tree.contentobject_id IN (' . $sqlHasSiteAccessImplode . ' ) AND ';

            return array( 'tables' => $sqlTables, 'joins'  => $sqlCond, 'columns' => $sqlColumns );
        }
        else
        {
            return array( 'tables' => false, 'joins'  => false, 'columns' => false );
        }
    }
}
