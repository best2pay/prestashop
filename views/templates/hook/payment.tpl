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
<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="bankwire" href="{$link->getModuleLink('best2pay', 'payment')|escape:'html':'UTF-8'}" title="{l s='Pay by debit or credit card' mod='best2pay'}">
				{l s='Pay by debit or credit card' mod='best2pay'}&nbsp;<span>{l s='(online)' mod='best2pay'}</span>
			</a>
		</p>
	</div>
</div>