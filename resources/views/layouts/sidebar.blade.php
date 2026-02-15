        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">

            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('buku.*') || request()->routeIs('kategori.*') ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#ui-basic">
                <span class="menu-title">Data Master</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse {{ request()->routeIs('buku.*') || request()->routeIs('kategori.*') ? 'show' : '' }}"
                  id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
                      href="{{ route('buku.index') }}">
                        Buku
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                      href="{{ route('kategori.index') }}">
                        Kategori
                    </a>
                </li>
                </ul>
              </div>
            </li>
            </li>
          </ul>
        </nav>