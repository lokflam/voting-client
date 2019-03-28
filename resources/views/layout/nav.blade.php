<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="{{ url('/') }}">Voting System</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="{{ url('vote') }}">Vote</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ url('ballot') }}">Ballot</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ url('batch') }}">Batch status</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="{{ url('admin') }}">Admin</a>
			</li>
		</ul>
	</div>
</nav>
