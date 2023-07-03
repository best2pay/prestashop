{*
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 *  @author    Best2Pay
 *  @copyright 2019-2022 Best2Pay
 *  @license   LICENSE.txt
 *
 */
*}

{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='best2pay'}">{l s='Checkout' mod='best2pay'}</a><span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Payment by debit or credit card' mod='best2pay'}
{/capture}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='best2pay'}</p>
{else}
<h3>{l s='Payment by debit or credit card' mod='best2pay'}</h3>
<form action="{$link->getModuleLink('best2pay', 'validation', [], true)|escape:'html':'UTF-8'}" method="post">
<p>
	<a href="http://www.best2pay.net" target="_blank"><img src="{$this_path_bw|escape:'htmlall':'UTF-8'}best2pay.png" alt="{l s='Best2Pay' mod='best2pay'}" style="float:left; margin: 0px 10px 5px 0px;" /></a>
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
