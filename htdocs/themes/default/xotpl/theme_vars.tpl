<!-- Assign Theme variables -->
<{if !$xoops_showlblock && $xoops_showrblock}>
<{assign var=col_span value=9}>
<{assign var=col_span_mid value=4}>
<{/if}>

<{if $xoops_showlblock && !$xoops_showrblock}>
<{assign var=col_span value=9}>
<{assign var=col_span_mid value=4}>
<{/if}>

<{if $xoops_showlblock && $xoops_showrblock}>
<{assign var=col_span value=6}>
<{assign var=col_span_mid value=3}>
<{/if}>

<{if !$xoops_showlblock && !$xoops_showrblock}>
<{assign var=col_span value=12}>
<{assign var=col_span_mid value=6}>
<{/if}>