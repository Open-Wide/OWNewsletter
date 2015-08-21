{def $base_uri = concat( 'newsletter/subscription_import/', $mailing_list.id )}
<div class="newsletter newsletter-subscription_import">
    <form enctype="multipart/form-data" name="SubscriptionImport" method="post" action={$base_uri|ezurl}>
        <div class="context-block">
            <div class="box-header">
                <div class="box-tc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tl">
                                <div class="box-tr">
                                    <h1 class="context-title">{"Import subscriptions"|i18n( 'newsletter/subscription/import' )}</h1>
                                    <div class="header-mainline">
                                    </div>
                                    {if is_set($warning)}
                                        <div class="message-warning">
                                            <h2>{$warning|wash}</h2>
                                        </div>
                                    {/if}
                                    {if is_set($log_url)}
                                        <div class="message-notice">
                                            <h2>
                                                {"The import is completed."|i18n( 'newsletter/subscription/import' )}
                                                {"For more imformation"|i18n( 'newsletter/subscription/import' )},
                                                <a href={$log_url|ezurl()}>{"follow this link."|i18n( 'newsletter/subscription/import' )}</a>
                                            </h2>
                                        </div>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">
                    <div class="context-attributes">
                        <div class="block float-break">
                        </div>
                        <div class="block">
                            <label>
                                {'Upload file'|i18n( 'newsletter/subscription/import' )}:
                                {if $upload_file|not()}
                                    <input type="hidden" name="MAX_FILE_SIZE" value="0" />
                                    <input name="UploadFile" type="file" />
                                {/if}
                            </label>
                            {if $upload_file}
                                <input name="UploadFile" type="hidden" value="{$upload_file}" />
                                <p><a href="/{$upload_file}">{$upload_file}</a></p>
                                {else}
                                <p>{'Expected file format:'|i18n( 'newsletter/subscription/import' )}</p>
                                {def $data_filed = ""
                                     $i=0}
                                <div></div>
                                <pre style="white-space:nowrap;">
                                    {foreach $all_fields as $field}
                                        {set $i=inc( $i )}
                                        {$field}{if ne($i, count($all_fields))};{/if}
                                    {/foreach}
                                    <br>
                                    {for 0 to 1 as $count}
                                        {set $i = 0}
                                        {if eq($count, 0)}user3@example.com;Julia;Mustermann;2{else}user4@example.com;Max;Mustermann;1{/if}{if $additional_fields};
                                        {foreach $additional_fields as $field}
                                            {set $i=inc( $i )}
                                            {if ezini_hasvariable(concat('AdditionalField_', $field), 'SelectOptions', 'newsletter.ini')}
                                                {set $data_filed = ezini(concat('AdditionalField_', $field), 'SelectOptions', 'newsletter.ini')}
                                                {foreach $data_filed as $key => $data offset $count max 1}
                                                    {$key}{if ne($i, count($additional_fields))};{/if}
                                                {/foreach}
                                            {else}
                                                XXXXXX{if ne($i, count($additional_fields))};{/if}
                                            {/if}

                                        {/foreach}
                                    {/if}
                                        {delimiter}<br>{/delimiter}
                                    {/for}
                                </pre>
                                {/if}
                        </div>
                        <div class="block">
                            <label>
                                {'Column delimiter'|i18n( 'newsletter/subscription/import' )}:
                                <select name="ColumnDelimiter">
                                    <option value=";" {if $column_delimiter|eq(';')}selected="selected"{/if}>;</option>
                                    <option value="," {if $column_delimiter|eq(',')}selected="selected"{/if}>,</option>
                                    <option value="|" {if $column_delimiter|eq('|')}selected="selected"{/if}>|</option>
                                </select>
                            </label>
                        </div>
                        <div class="block">
                            <label>
                                {'The first line is the column headings'|i18n( 'newsletter/subscription/import' )}:
                                <input type="checkbox" name="FirstLineIsColumnHeadings" {if $first_line_is_column_headings}checked="checked"{/if}/>
                            </label>
                        </div>
                        {if $preview}
                            <div class="block">
                                <label>
                                    {'Preview'|i18n( 'newsletter/subscription/import' )}:
                                </label>
                                <table class="list">
                                    <thead>
                                        <tr>
                                            <th>{'Row number'|i18n( 'newsletter/subscription/import' )}</th>
                                            {foreach $all_fields as $field}
                                                <th>{$field|i18n( 'newsletter/additional_fields' )}</th>
                                            {/foreach}
                                        </tr>
                                    </thead>
                                    <tboby>
                                        {foreach $preview as $row}
                                            <tr>
                                                <td>{$row.row_number}</td>
                                                {foreach $all_fields as $field}
                                                    <td>{$row.$field}</td>
                                                {/foreach}
                                            </tr>
                                        {/foreach}
                                    </tboby>
                                </table>
                            </div>
                        {/if}
                        <div class="break">
                        </div>
                    </div>
                </div>
                <div class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tc">
                                    <div class="box-bl">
                                        <div class="box-br">
                                            <div class="left">
                                                <input type="hidden" name="RedirectUrlActionSuccess" value="{$redirect_url_action_success}" />
                                                {if $preview}
                                                    <input class="button" type="submit" name="ImportButton" value="{'Run import'|i18n( 'newsletter/subscription/import' )}" title="{'Import subscriptions.'|i18n( 'newsletter/subscription/import' )}" />
                                                {else}
                                                    <input class="button" type="submit" name="PreviewButton" value="{'Import'|i18n( 'newsletter/subscription/import' )}" title="{'Import subscriptions.'|i18n( 'newsletter/subscription/import' )}" />
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
