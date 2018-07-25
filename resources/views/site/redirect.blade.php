<html>
<body>
@include('_partial.metrika')
<script type="text/javascript">
	// Redirect
	setTimeout(function() {
		window.location.href = '{!! $url ?: url('/') !!}';
	}, 1000);
</script>
</body>
</html>
