<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>CV {{ $generals['name'] }}</title>

    <link rel="stylesheet" href="css/cv.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <div class="container">
        <header class="w3-row">
            <div class="w3-col s6">
                <h1 id="name">{{ $generals['name'] }}</h1>
                <div id="designation">
                    <span class="title"><i>{{ $generals['occupation'] }}</i></span><br>
                </div>
            </div>
            <div class="w3-col s6 w3-right-align">
                <div id="designation">
                    <span class="github"><a href="https://github.com/lexisvar/">Github</a></span><br>
                    <span class="linkedin"><a href="https://www.linkedin.com/in/lexisvar/">LinkedIn</a></span><br>
                    <span class="page"><a href="https://lexisvar.me/">https://lexisvar.me</a></span>
                </div>
                <div class="contact">
                    <div class="email">{{ $generals['email'] }}</div>
                    <div class="address">{{ $generals['address'] }}</div>
                    <div class="phone">{{ $generals['phone'] }}</div>
                    <div class="language">{{ $generals['language'] }}</div>
                </div>
            </div>
        </header>
        <div class="content" role=main>
            <section id="objective">
                <h2 class="title">About</h2>
                <hr class="hr-title">
                <div class="description">
                    <p>
                    {{ $generals['biography'] }}
                    </p>
                </div> <!-- end description -->
            </section>
            <section id="experience">
                <h2 class="title">Experience</h2>
                <hr>
                <div class="description">
                    <ul>
                        @foreach ($experiences as $experience)
                        <!-- Project 1 -->
                        <li>
                            <section class="project">
                                <h3 class="title">{{$experience->occupation}}</h3>
                                <div class="meta">
                                    <span class="field">{{$experience->company}}</span>
                                    <span class="date">{{ date('M Y', strtotime($experience->start_date)) }} - {{ $experience->finish_date ? date('M Y', strtotime($experience->finish_date)) : 'CURRENT' }}</span>
                                </div>
                                <p class="description">{{$experience->description}} </p>
                            </section> <!-- end project description -->
                        </li>
                        @endforeach
                    </ul>
                </div>
            </section>
            <section id="education">
                <h2 class="title">Education</h2>
                <hr>
                <div class="description">
                    <ul>
                        @foreach ($educations as $education)
                        <!-- Project 1 -->
                        <li>
                            <section class="project">
                                <h3 class="title">{{$education->study_name}}</h3>
                                <div class="meta">
                                    <span>{{$education->college}}</span> <span class="date">{{$education->start_date}} - {{$education->finish_date}}</span>
                                </div>
                                <p class="description">{{$education->description}} </p>
                            </section> <!-- end project description -->
                        </li>
                        @endforeach
                    </ul>
                </div>
            </section>
            <section id="skills">
                <h2 class="title">Skills</h2>
                <hr>
                <div class="description">
                    <div style="float: left; width: 50%;">
                        <ul>
                            @php $half = ceil(count($skills) / 2); @endphp
                            @foreach ($skills->take($half) as $skill)
                            <li>
                                <section class="skill">
                                    <h6 class="title">{{$skill->name}}: {{$skill->percent}}%</h6>
                                </section>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div style="float: right; width: 50%;">
                        <ul>
                            @foreach ($skills->skip($half) as $skill)
                            <li>
                                <section class="skill">
                                    <h6 class="title">{{$skill->name}}: {{$skill->percent}}%</h6>
                                </section>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div style="clear: both;"></div>
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
