<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Project de fin d'etude</title>
    <link rel="stylesheet" href="{{url('/css/style.css')}}">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  @include('layouts.sidebar')
  <!--La partie principal-->
  <section class="home-section">
    <div class="home-content">
      <div class="button">
        <button class="setting-btn">
          <span class="bar bar1"></span>
          <span class="bar bar2"></span>
          <span class="bar bar1"></span>
        </button>
      </div>
      <div class="rechercher">
        <h1>Gestion des utilisateur</h1>
        <div class="group">
          <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
            <g>
              <path
                d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
              ></path>
            </g>
          </svg>
        
          <input
            id="query"
            class="input"
            type="search"
            placeholder="Recherche d'utilisateur"
            name="searchbar"
          />
        </div> 

        <form action="{{route('user.create')}}" method="post">
            @csrf
            <Label>Nom</Label>
            <input type="text" name="nom">
            <Label>Email</Label>
            <input type="email" name="email">
            <Label>Password</Label>
            <input type="password" name="password">

            <button type="submit">Ajouter Utilisateur</button>


          
        </form>
        <main class="table" id="customers_table">
        <section class="table__body">
             
              
          </section>
      </main>
        


      </div>
      </div>
  </section>
  <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e)=>{
     let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
     arrowParent.classList.toggle("showMenu");
      });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".setting-btn");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("close");
    });
    </script>
</body>
</html>