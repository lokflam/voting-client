<?php if(session('error')): ?>
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
<?php endif; ?>
<?php if(session('status')): ?>
    <div class="alert alert-success" role="alert">{{ session('status') }}</div>
<?php endif; ?>