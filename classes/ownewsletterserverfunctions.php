<?php

class OWNewsletterServerFunctions extends ezjscServerFunctions {

	/**
	 * Returns a subtree node items for given parent node
	 *
	 * Following parameters are supported:
	 * ezjscnode::subtree::parent_node_id::limit::offset::sort::order
	 *
	 * @since 1.2
	 * @param mixed $args
	 * @return array
	 */
	public static function subTree( $args ) {
		$parentNodeID = isset( $args[0] ) ? $args[0] : null;
		$limit = isset( $args[1] ) ? $args[1] : 25;
		$offset = isset( $args[2] ) ? $args[2] : 0;
		$sort = isset( $args[3] ) ? self::sortMap( $args[3] ) : 'published';
		$order = isset( $args[4] ) ? $args[4] : false;
		$objectNameFilter = isset( $args[5] ) ? $args[5] : '';

		if ( !$parentNodeID ) {
			throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', 'Parent node id is not valid' );
		}

		$node = eZContentObjectTreeNode::fetch( $parentNodeID );
		if ( !$node instanceOf eZContentObjectTreeNode ) {
			throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', "Parent node '$parentNodeID' is not valid" );
		}

		$params = array( 'Depth' => 1,
			'Limit' => $limit,
			'Offset' => $offset,
			'SortBy' => array( array( $sort, $order ) ),
			'DepthOperator' => 'eq',
			'ObjectNameFilter' => $objectNameFilter,
			'AsObject' => true );

		// fetch nodes and total node count
		$count = $node->subTreeCount( $params );
		if ( $count ) {
			$nodeArray = $node->subTree( $params );
		} else {
			$nodeArray = array();
		}
		unset( $node ); // We have on purpose not checked permission on $node itself, so it should not be used
		// generate json response from node list
		if ( $nodeArray ) {
			$list = ezjscAjaxContent::nodeEncode( $nodeArray, array( 'formatDate' => 'shortdatetime',
						'fetchThumbPreview' => true,
						'fetchSection' => true,
						'fetchCreator' => true,
						'fetchClassIcon' => true ), 'raw' );
			$list = self::completeInfo( $list );
		} else {
			$list = array();
		}

		return array( 'parent_node_id' => $parentNodeID,
			'count' => count( $nodeArray ),
			'total_count' => (int) $count,
			'list' => $list,
			'limit' => $limit,
			'offset' => $offset,
			'sort' => $sort,
			'order' => $order );
	}

	/**
	 * A helper function which maps sort keys from encoded JSON node
	 * to supported values
	 *
	 * @since 1.2
	 * @param string $sort
	 * @return string
	 */
	protected static function sortMap( $sort ) {
		switch ( $sort ) {
			case 'modified_date':
				$sortKey = 'modified';
				break;
			case 'published_date':
				$sortKey = 'published';
				break;
			default:
				$sortKey = $sort;
		}

		return $sortKey;
	}

	protected static function completeInfo( $list ) {
		foreach ( $list as $index => $object ) {
			// Class icon
			$newsletterEdition = OWNewsletterEdition::fetchByCustomConditions( array(
						'contentobject_id	' => $object['id'],
						'contentobject_attribute_version' => $object['version']
					) );
			if ( $newsletterEdition instanceof OWNewsletterEdition ) {
				$status = $newsletterEdition->attribute( 'status' );
				$statusName = $newsletterEdition->attribute( 'status_name' );
			} else {
				$status = OWNewsletterEdition::STATUS_DRAFT;
				$statusName = ezpI18n::tr( 'newsletter/edition/status', 'Draft' );
			}
			$operator = new eZURLOperator();
			$tpl = eZTemplate::instance();
			$operatorValue = 'images/newsletter/icons/crystal-newsletter/16x16/newsletter_' . $status . '.png';
			$operatorParameters = array();
			$namedParameters = array( 'quote_val' => 'no' );
			$operatorName = 'ezdesign';
			$operator->modify(
					$tpl, $operatorName, $operatorParameters, '', '', $operatorValue, $namedParameters, array()
			);
			$list[$index]['class_icon'] = '<img src="' . $operatorValue . '" width="16" height="16" alt="' . $object['class_name'] . ' [' . $statusName . ']" title="' . $object['class_name'] . ' [' . $statusName . ']" />';
		}
		return $list;
	}

}
