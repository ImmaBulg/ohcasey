<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script type="application/javascript" src="{{ url('js/jquery.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/bootstrap.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/js.cookie.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/store.js') }}"></script>
	<script type="application/javascript" src="{{ _el('js/common.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/perfect-scrollbar.jquery.js') }}"></script>
</head>
<body>
	<script>
		var env = getEnv();

		if (env) {
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = '{{ url('compile') }}';

			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'current';
			input.value = JSON.stringify(env);
			form.appendChild(input);

			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = '_token';
			input.value = '{{ csrf_token() }}';
			form.appendChild(input);

			document.body.appendChild(form);
			form.submit();
		} else {
			document.body.appendChild(document.createTextNode('Empty constructor'));
		}
	</script>
</body>
</html>
