<div id="best2pay_block"></div>
<script defer="defer" src="{$best2pay_url}/static/common/scripts/modalPayform.bundle.js"></script>
<script>
	let modal;
	let userWaiting;
	window.addEventListener("load", () => {
		modal = modalPayform("{$action_path}");
		let best2pay_option = $("#payment-option-{$option_id}");
		let submit_button = $("#best2pay_block").closest("div.content").find('button[type="submit"]');
		submit_button.click(function (e){
			if(best2pay_option.is(":checked") && !$(this).hasClass("disabled")){
				modal.openModal();
				startTimer();
				return false;
			}
			return true;
		});
	});
	let observer = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			[].filter.call(mutation.addedNodes, function (node) {
				return node.id === 'payform-modal'; //
			}).forEach(function (node) {
				let button = node.querySelector('#payform-close-button');
				button.addEventListener('click', function (e){
					redirectToOrderHistory();
				});
			});
		});
	});
	observer.observe(document.body, { childList: true, subtree: true });
	function startTimer() {
		userWaiting = setTimeout(() => {
			clearTimeout(userWaiting);
			redirectToOrderHistory();
		}, 5 * 60 * 1000);
	}
	function redirectToOrderHistory() {
		window.top.location.href = '{$order_history}';
	}

</script>