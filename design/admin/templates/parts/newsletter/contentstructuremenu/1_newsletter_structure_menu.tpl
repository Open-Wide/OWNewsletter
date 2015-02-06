{literal}
    <style type='text/css'>
        div#contentstructure ul#content_tree_menu ul li { padding-left: 0px; }div#contentstructure ul#content_tree_menu ul ul { margin-left: 20px; }
    </style>
{/literal}

<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibcookiesupport.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibdomsupport.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibimagepreloader.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/contentstructuremenu/contentstructuremenu.js"|ezdesign}></script>

{def $root_node_id             = 1
     $class_filter            = ezini( 'TreeMenu', 'ShowClasses'      , 'contentstructuremenu.ini' )
     $max_depth               = ezini( 'TreeMenu', 'MaxDepth'         , 'contentstructuremenu.ini' )
     $max_nodes               = ezini( 'TreeMenu', 'MaxNodes'         , 'contentstructuremenu.ini' )
     $sortBy                 = ezini( 'TreeMenu', 'SortBy'           , 'contentstructuremenu.ini' )
     $fetch_hidden            = ezini( 'SiteAccessSettings', 'ShowHiddenNodes', 'site.ini'         )
     $item_click_action        = ezini( 'TreeMenu', 'ItemClickAction'  , 'contentstructuremenu.ini' )
     $class_icons_size         = ezini( 'TreeMenu', 'ClassIconsSize'   , 'contentstructuremenu.ini' )
     $preload_class_icons      = ezini( 'TreeMenu', 'PreloadClassIcons', 'contentstructuremenu.ini' )
     $autoopen_current_node    = ezini( 'TreeMenu', 'AutoopenCurrentNode', 'contentstructuremenu.ini' )
     $content_structure_tree   = false()
     $menu_id                 = "content_tree_menu"
     $is_depth_unlimited       = eq($max_depth, 0)
     $root_node               = false()}

{* check size of icons *}
{if is_set($class_icons_size)}
    {set $class_icons_size=$class_icons_size}
{/if}

{* load icons if preload_class_icons is enabled *}
{if eq( $preload_class_icons, "enabled" )}
    {def $icon_info           = icon_info( class )
         $icons_theme_path    = $icon_info.theme_path
         $icons_list          = $icon_info.icons
         $default_icon        = $icon_info.default
         $icon_size_path      = $icon_info.size_path_list[$class_icons_size] }

    <script language="JavaScript" type="text/javascript"><!--

        var iconsList = new Array();
        var wwwDirPrefix = "{ezsys('wwwdir')}";
        var iconPath = "";

        // oridinary icons.
        {foreach $icons_list as $icon}
        iconPath = wwwDirPrefix + "/" + "{$icons_theme_path}" + "/" + "{$icon_size_path}" + "/" + "{$icon}";
        iconsList.push(iconPath);
        {/foreach}

        // default icon.
        iconPath = wwwDirPrefix + "/" + "{$icons_theme_path}" + "/" + "{$icon_size_path}" + "/" + "{$default_icon}";
        iconsList.push(iconPath);

        // load them all!!
        ezjslib_preloadImageList(iconsList);

        // -->
    </script>
    {undef $icon_info $icons_theme_path $icons_list $default_icon $icon_size_path}
{/if}

{* check custom_root_node *}
{if is_set( $custom_root_node_id )}
    {set $root_node_id=$custom_root_node_id}
{/if}

{set $root_node=fetch( 'content', 'node', hash( node_id, $root_node_id ) )}

{* check custom action when clicking on menu item *}
{if and( is_set( $csm_menu_item_click_action ), eq( $item_click_action, '' ) )}
    {set $item_click_action=$csm_menu_item_click_action}
{/if}

{* if menu action is set translate it to url *}
{if eq( $item_click_action, '' )|not()}
    {set $item_click_action = $item_click_action|ezurl(no)}
{/if}

{* create menu *}
{* Show menu tree. All container nodes are unfolded. *}
<ul id="{$menu_id}">
    {include uri="design:parts/newsletter/contentstructuremenu/2_newsletter_system_tree.tpl" class_icons_size=$class_icons_size csm_menu_item_click_action=$item_click_action ui_context=$ui_context is_root_node=true()}
</ul>

{* initialize menu *}
<script language="JavaScript" type="text/javascript"><!--

    {* get path to current node which consists of nodes ids *}
    var nodesList = new Array();

    {foreach $module_result.path as $path}
        {if and(is_set($path.node_id), or($is_depth_unlimited, $max_depth|gt(0)))}
    nodesList.push("n{$path.node_id}");
            {set $max_depth = dec($max_depth)}
        {/if}
    {/foreach}


    ezcst_setFoldUnfoldIcons({"images/content_tree-open.gif"|ezdesign}, {"images/content_tree-close.gif"|ezdesign}, {"images/1x1.gif"|ezdesign});
    ezcst_initializeMenuState(nodesList, "{$menu_id}", "{$autoopen_current_node}");
    // -->
</script>
{undef}

