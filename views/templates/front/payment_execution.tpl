{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='best2pay'}">{l s='Checkout' mod='best2pay'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Payment by debit or credit card' mod='best2pay'}
{/capture}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='best2pay'}</p>
{else}
<h3>{l s='Payment by debit or credit card' mod='best2pay'}</h3>
<form action="{$link->getModuleLink('best2pay', 'validation', [], true)|escape:'html'}" method="post">
<p>
	<a href="http://www.best2pay.net" target="_blank"><img src="{$this_path_bw}best2pay.png" alt="{l s='Best2Pay' mod='best2pay'}" style="float:left; margin: 0px 10px 5px 0px;" /></a>
	{l s='We accept major kinds of bank cards including Visa and MasterCard in partnership with Best2Pay, which provides of secure online transactions processing.' mod='best2pay'}
	<br/><br />
	{l s='By clicking the \'Make payment\' button below, you will be redirected to Best2Pay payment gateway to complete the payment.' mod='best2pay'}
</p>
<br/>
<p class="cart_navigation" id="cart_navigation">
	<input type="hidden" name="stub" value="stub" />
	<input type="submit" value="{l s='Make payment' mod='best2pay'}" class="exclusive_large" />
</p>
</form>
{/if}
