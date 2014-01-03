<div class="newsletter newsletter-unsubscribe">
    <div class="border-box">
		<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
		<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
					<h1>{'Unsubscribe'|i18n( 'newsletter/unsubscribe' )}</h1>
					<p>{'If you no longer wish to receive email from our website, confirm your unsubscribe from our mailing lists.'|i18n( 'newsletter/unsubscribe')}</p>
					<form method="post" action={concat('newsletter/unsubscribe/', $newsletter_user.hash)|ezurl()}>
						<input class="button" type="submit" name="UnsubscribeButton" value="{'Unsubscribe'|i18n( 'newsletter/unsubscribe' )}" title="{'Unsubscribe from all lists.'|i18n( 'newsletter/unsubscribe' )}" />
						<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'newsletter/unsubscribe' )}" />
					</form>
						<p>{'If you want to change your subscription'|i18n( 'newsletter/unsubscribe')},
							<a href={concat('newsletter/configure/', $newsletter_user.hash)|ezurl()}>{'follow this link.'|i18n( 'newsletter/unsubscribe')}</a></p>
				</div></div></div>
		<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>
</div>