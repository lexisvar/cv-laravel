<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CV Alexis Vargas</title>
	
	<link rel="stylesheet" href="css/cv.css" />
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<div class="container">
	<header>
		<h1 id="name">Alexis Vargas</h1>
		
		<div id="designation">
		
			<span class="title">Full Stack Developer (PHP, Ruby, VueJS)</span>
			<span class="organization">Shore Canada</span>
		
		</div>
		
		<div class="contact">
			<div class="email">lexisvar@gmail.com</div>
		</div>
		
	</header>
	
	<div class="content" role=main>
	
		<section id="objective">
			<h2 class="title">About</h2>
			<div class="description">
				<p>
					Hello! I am Anthony Barnett. Web Developer, Graphic Designer and Photographer.

          Creative CV is a HTML resume template for professionals. Built with Bootstrap 4, 
          Now UI Kit and FontAwesome, this modern and responsive design template is perfect to 
          showcase your portfolio, skills and experience. Learn More
				</p>			
			</div> <!-- end description -->
      
		</section>

    
		
    
		<section id="experience">
			<h2 class="title">Experience</h2>
			<div class="description">				
        @foreach ($experiences as $experience)
          <!-- Project 1 -->
          <section class="project">
            <h3 class="title">{{$experience->occupation}}</h3>
            <div class="meta">
              <span class="field">{{$experience->company}}</span> <span class="date">{{$experience->start_date}} - {{$experience->finish_date}}</span>
            </div>
            <p class="description">{{$experience->description}} </p>
          </section> <!-- end project description -->
        @endforeach
      </div>
		</section>
			
		<section id="education">
			<h2 class="title">Education</h2>
			<div class="description">				
        @foreach ($educations as $education)
          <!-- Project 1 -->
          <section class="project">
            <h3 class="title">{{$education->occupation}}</h3>
            <div class="meta">
              <span class="field">{{$education->college}}</span> <span class="date">{{$education->start_date}} - {{$education->finish_date}}</span>
            </div>
            <p class="description">{{$education->description}} </p>
          </section> <!-- end project description -->
        @endforeach
      </div>
		</section>

    <section id="skills">
			<h2 class="title">Skills</h2>
			<div class="description">				
        @foreach ($skills as $skill)
          
          <section class="skill">
            <div class="w3-light-grey">
              <div class="w3-grey" style="height:24px;width:<?= $skill->percent ?>%;"> {{$skill->percent}}% </div>
            </div><br>
          </section> 
        @endforeach
      </div>
		</section>
	

		<!-- Training/ seminars -->
		
		
		
		<!-- Scholistics Achievements -->
		
		
		
		<!-- Technical Skills -->
		
		
		<!-- Extra-Curricular Activities -->
		
		
		<!-- Personal Informations -->
		
		
		<!-- References -->
		
		
		
		<!-- Declarations -->

	
		<!-- signatory -->
	
	
	
	
	</div> <!-- end main content -->
	
	
	
	
	
	
	
	
	
	
	
	
	
	</div> <!-- end container -->
	
</body>
</html>