 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <nav class="navbar header-navbar pcoded-header">
     <div class="navbar-wrapper">
         <div class="navbar-logo">
             <a class="mobile-menu" id="mobile-collapse" href="#!">
                 <i class="feather icon-menu"></i>
             </a>
             <a href="#">
                 <img class="img-fluid" src="{{ asset('assets/profe/logoprofe.png') }}" alt="Theme-Logo" height="65"
                     width="100" />
             </a>
             <a class="mobile-options">
                 <i class="feather icon-more-horizontal"></i>
             </a>
         </div>
         <div class="navbar-container">
             <ul class="nav-left">
                 {{-- <li class="header-search">
                     <div class="main-search morphsearch-search">
                         <div class="input-group">
                             <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                             <input type="text" class="form-control" />inscripcionbuscadorper
                             <span class="input-group-addon search-btn"><i class="feather icon-search"></i></span>
                         </div>
                     </div>
                 </li> --}}
                 <li>
                     <a href="#!" onclick="javascript:toggleFullScreen()">
                         <i class="feather icon-maximize full-screen"></i>
                     </a>
                 </li>
             </ul>
             <ul class="nav-right">
                 {{-- <li class="header-notification">
                     <div class="dropdown-primary dropdown">
                         <div class="dropdown-toggle" data-toggle="dropdown">
                             <i class="feather icon-bell"></i>
                             <span class="badge bg-c-pink">5</span>
                         </div>
                         <ul class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn"
                             data-dropdown-out="fadeOut">
                             <li>
                                 <h6>Notifications</h6>
                                 <label class="label label-danger">New</label>
                             </li>
                             <li>
                                 <div class="media">
                                     <img class="d-flex align-self-center img-radius"
                                         src="files/assets/images/avatar-4.jpg" alt="Generic placeholder image" />
                                     <div class="media-body">
                                         <h5 class="notification-user">John Doe</h5>
                                         <p class="notification-msg">
                                             Lorem ipsum dolor sit amet, consectetuer elit.
                                         </p>
                                         <span class="notification-time">30 minutes ago</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <div class="media">
                                     <img class="d-flex align-self-center img-radius"
                                         src="files/assets/images/avatar-3.jpg" alt="Generic placeholder image" />
                                     <div class="media-body">
                                         <h5 class="notification-user">Joseph William</h5>
                                         <p class="notification-msg">
                                             Lorem ipsum dolor sit amet, consectetuer elit.
                                         </p>
                                         <span class="notification-time">30 minutes ago</span>
                                     </div>
                                 </div>
                             </li>
                             <li>
                                 <div class="media">
                                     <img class="d-flex align-self-center img-radius"
                                         src="files/assets/images/avatar-4.jpg" alt="Generic placeholder image" />
                                     <div class="media-body">
                                         <h5 class="notification-user">Sara Soudein</h5>
                                         <p class="notification-msg">
                                             Lorem ipsum dolor sit amet, consectetuer elit.
                                         </p>
                                         <span class="notification-time">30 minutes ago</span>
                                     </div>
                                 </div>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="header-notification">
                     <div class="dropdown-primary dropdown">
                         <div class="displayChatbox dropdown-toggle" data-toggle="dropdown">
                             <i class="feather icon-message-square"></i>
                             <span class="badge bg-c-green">3</span>
                         </div>
                     </div>
                 </li> --}}
                 <li class="user-profile header-notification">
                     <div class="dropdown-primary dropdown">
                         @include('backend.layouts.partials.logout')
                     </div>
                 </li>
             </ul>
         </div>
     </div>
 </nav>

 
 {{-- <div id="sidebar" class="users p-chat-user showChat">
     <div class="had-container">
         <div class="card card_main p-fixed users-main">
             <div class="user-box">
                 <div class="chat-inner-header">
                     <div class="back_chatBox">
                         <div class="right-icon-control">
                             <input type="text" class="form-control search-text" placeholder="Search Friend"
                                 id="search-friends" />
                             <div class="form-icon">
                                 <i class="icofont icofont-search"></i>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="main-friend-list">
                     <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe"
                         data-toggle="tooltip" data-placement="left" title="Josephin Doe">
                         <a class="media-left" href="#!">
                             <img class="media-object img-radius img-radius" src="files/assets/images/avatar-3.jpg"
                                 alt="Generic placeholder image " />
                             <div class="live-status bg-success"></div>
                         </a>
                         <div class="media-body">
                             <div class="f-13 chat-header">Josephin Doe</div>
                         </div>
                     </div>
                     <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe"
                         data-toggle="tooltip" data-placement="left" title="Lary Doe">
                         <a class="media-left" href="#!">
                             <img class="media-object img-radius" src="files/assets/images/avatar-2.jpg"
                                 alt="Generic placeholder image" />
                             <div class="live-status bg-success"></div>
                         </a>
                         <div class="media-body">
                             <div class="f-13 chat-header">Lary Doe</div>
                         </div>
                     </div>
                     <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice"
                         data-toggle="tooltip" data-placement="left" title="Alice">
                         <a class="media-left" href="#!">
                             <img class="media-object img-radius" src="files/assets/images/avatar-4.jpg"
                                 alt="Generic placeholder image" />
                             <div class="live-status bg-success"></div>
                         </a>
                         <div class="media-body">
                             <div class="f-13 chat-header">Alice</div>
                         </div>
                     </div>
                     <div class="media userlist-box" data-id="4" data-status="online" data-username="Alia"
                         data-toggle="tooltip" data-placement="left" title="Alia">
                         <a class="media-left" href="#!">
                             <img class="media-object img-radius" src="files/assets/images/avatar-3.jpg"
                                 alt="Generic placeholder image" />
                             <div class="live-status bg-success"></div>
                         </a>
                         <div class="media-body">
                             <div class="f-13 chat-header">Alia</div>
                         </div>
                     </div>
                     <div class="media userlist-box" data-id="5" data-status="online" data-username="Suzen"
                         data-toggle="tooltip" data-placement="left" title="Suzen">
                         <a class="media-left" href="#!">
                             <img class="media-object img-radius" src="files/assets/images/avatar-2.jpg"
                                 alt="Generic placeholder image" />
                             <div class="live-status bg-success"></div>
                         </a>
                         <div class="media-body">
                             <div class="f-13 chat-header">Suzen</div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div> --}}
