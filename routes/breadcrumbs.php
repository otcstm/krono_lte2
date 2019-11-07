<?php

// Home
Breadcrumbs::for('misc.home', function ($trail) {
    $trail->push('Home', route('misc.home', [], false));
});

// shift planning
Breadcrumbs::for('shift.index', function ($trail) {
    $trail->parent('misc.home');
    $trail->push('Plan Shift', route('shift.index', [], false));
});

Breadcrumbs::for('shift.view', function ($trail, $shiftgrp) {
    $trail->parent('shift.index');
    $trail->push($shiftgrp->name . ' - ' . $shiftgrp->plan_month->format('M-Y'), route('shift.view', ['id' => $shiftgrp->id], false));
});

Breadcrumbs::for('shift.staff', function ($trail, $shiftstaff) {
    $trail->parent('shift.view', $shiftstaff->ShiftPlan);
    $trail->push($shiftstaff->User->name, route('shift.staff', ['id' => $shiftstaff->id], false));
});
