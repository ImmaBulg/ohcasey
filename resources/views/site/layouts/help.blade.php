<div id="help" class="hidden fade">
	<div>
		<svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<defs>
				<filter id="help-drop-shadow" x="-50%" y="-50%" width="200%" height="200%">
					<feFlood result="flood" flood-color="#ffffff" flood-opacity="1"></feFlood>
					<feComposite in="flood" result="mask" in2="SourceGraphic" operator="in"></feComposite>
					<feMorphology in="mask" result="dilated" operator="dilate" radius="2"></feMorphology>
					<feGaussianBlur in="dilated" result="blurred" stdDeviation="10"></feGaussianBlur>
					<feMerge>
						<feMergeNode in="blurred"></feMergeNode>
						<feMergeNode in="SourceGraphic"></feMergeNode>
					</feMerge>
				</filter>
				<mask id="help-mask">
					<rect x="0" y="0" width="100%" height="100%" fill="rgba(255,255,255,0.8)"></rect>
					<g id="help-rect"></g>
				</mask>
			</defs>

			<rect x="0" y="0" width="100%" height="100%" fill="black" mask="url(#help-mask)"></rect>
		</svg>
		<div id="help-text"></div>
	</div>
</div>
