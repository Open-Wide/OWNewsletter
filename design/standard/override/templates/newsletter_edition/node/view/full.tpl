<div class="border-box">
	<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
	<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
				{def $newsletter_edition = $node|newsletter_edition_content()
					 $sending = $newsletter_edition.sending
					 $output = $sending.output}
				{if $output.content_type|eq('text/html')}
					{$output.body.html}
				{elseif $output.content_type|eq('multipart/alternative')}
					{$output.body.html}
					<hr /><pre>{$output.body.text}</pre>
					{else}
				<pre>{$output.body.text}</pre>
				{/if}				
			</div></div></div>
	<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>