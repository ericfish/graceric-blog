		function submitSearch()
		{
			if(window.navigator.userAgent.indexOf("MSIE")>=1) {
			xajax.$('submit').disabled=true;
			xajax.$('submit').value="please wait...";
			}
			xajax_processSearchForm(xajax.getFormValues("searchForm"));
			return false;
		}