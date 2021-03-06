<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}">Voting System Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/vote') }}">Vote</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Blockchain
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ url('admin/blockchain/batch') }}">Batch</a>
                    <a class="dropdown-item" href="{{ url('admin/blockchain/block') }}">Block</a>
                    <a class="dropdown-item" href="{{ url('admin/blockchain/transaction') }}">Transaction</a>
                    <a class="dropdown-item" href="{{ url('admin/blockchain/state') }}">State</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
    