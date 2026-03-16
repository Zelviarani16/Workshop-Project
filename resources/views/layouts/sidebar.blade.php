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
        <li class="nav-item {{ request()->routeIs('buku.*') || request()->routeIs('kategori.*') ? 'active' : '' }}">
              <!-- href = #ui basic, artinya kalau link itu di klik bootstrap akan cari elemen dgn id ui-basic.  data-bs-toggle="collapse" → memberi tahu Bootstrap bahwa ini tombol dropdown -->
              <a class="nav-link {{ request()->routeIs('buku.*') || request()->routeIs('kategori.*') ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#ui-basic">
                <span class="menu-title">Data Master</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <!-- Kalau route yg sedang aktif .*, maka class show ditambahkan. show = collapse dalam keadaan terbuka -->
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
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sertifikat.*') ? 'active' : '' }}"
                      href="{{ route('sertifikat.generate') }}">
                        Sertifikat
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('undangan.*') ? 'active' : '' }}"
                      href="{{ route('undangan.generate') }}">
                        Undangan
                    </a>
                  </li>

                    <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}"
                      href="{{ route('barang.index') }}">
                        Barang
                    </a>
                  </li>

                  
                    <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tabel-biasa') ? 'active' : '' }}"
                      href="{{ route('tm4.tabel-biasa') }}">
                        Tabel Biasa
                    </a>
                  </li>


                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('datatables') ? 'active' : '' }}"
                      href="{{ route('tm4.datatables') }}">
                        DataTables
                    </a>
                  </li>

                  
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('select') ? 'active' : '' }}"
                      href="{{ route('tm4.select') }}">
                        Select & Select2
                    </a>
                  </li>


                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('wilayah') ? 'active' : '' }}"
                      href="{{ route('wilayah.index') }}">
                        Wilayah
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir') ? 'active' : '' }}"
                      href="{{ route('kasir.index') }}">
                        Kasir
                    </a>
                  </li>


                </ul>
              </div>
            </li>
            </li>
          </ul>
        </nav>