      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">City of Loan</span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2" aria-label="Main navigation">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              @if (auth()->user()->role === 'customer')
                <li class="nav-item">
                  <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-cash-stack"></i>
                    <p>Loan Management</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('loans.create') }}" class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-plus-circle"></i>
                    <p>Apply for Loan</p>
                  </a>
                </li>
              @endif

              @if (in_array(auth()->user()->role, ['loan_officer', 'admin'], true))
                <li class="nav-item">
                  <a href="{{ route('loans.pending') }}" class="nav-link {{ request()->routeIs('loans.pending') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-hourglass-split"></i>
                    <p>Pending Loans</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.index', 'loans.show') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-cash-stack"></i>
                    <p>Loan Management</p>
                  </a>
                </li>
              @endif

              @if (auth()->user()->role === 'customer')
                <li class="nav-item">
                  <a href="{{ route('loans.overdue') }}" class="nav-link {{ request()->routeIs('loans.overdue') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-exclamation-triangle"></i>
                    <p>Overdue Payments</p>
                  </a>
                </li>
              @endif

              @if (auth()->user()->role === 'cashier')
                <li class="nav-item">
                  <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*', 'schedules.*', 'repayments.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-cash-coin"></i>
                    <p>Loan Repayments</p>
                  </a>
                </li>
              @endif

              @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                  <a href="{{ route('dashboard.overdue') }}" class="nav-link {{ request()->routeIs('dashboard.overdue') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-bar-chart-line"></i>
                    <p>Overdue Dashboard</p>
                  </a>
                </li>
              @endif

              @if (in_array(auth()->user()->role, ['admin', 'loan_officer'], true))
                <li class="nav-item">
                  <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>Customer</p>
                  </a>
                </li>
              @endif

              @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                  <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>Employee</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('Categories.index') }}" class="nav-link {{ request()->routeIs('Categories.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>Categories</p>
                  </a>
                </li>
              @endif
              <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>Profile</p>
                </a>
              </li>
            </ul>
            <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
              <a
                href="{{ route('logout') }}"
                class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
                onclick="event.preventDefault(); this.closest('nav').querySelector('#sidebar-logout').submit();"
              >
                <i class="bi bi-lock" aria-hidden="true"></i>
                Logout
              </a>
              <form id="sidebar-logout" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
              </form>
            </div>
          </nav>
        </div>
      </aside>