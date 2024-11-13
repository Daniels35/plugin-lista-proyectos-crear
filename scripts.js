jQuery(document).ready(function($) {
    var currentIndex = 0;
    var projects = $('.executed-projects .custom-project');
    var projectCount = projects.length;
    var projectsPerView = 3;
    var autoScroll;

    function updateProjects() {
        var offset = -currentIndex * 100 / projectsPerView;
        $('.executed-projects').css('transform', 'translateX(' + offset + '%)');
    }

    function nextProject() {
        currentIndex = (currentIndex + 1) % Math.ceil(projectCount / projectsPerView);
        updateProjects();
    }

    function prevProject() {
        currentIndex = (currentIndex - 1 + Math.ceil(projectCount / projectsPerView)) % Math.ceil(projectCount / projectsPerView);
        updateProjects();
    }

    $('.executed-projects-next').on('click', nextProject);
    $('.executed-projects-prev').on('click', prevProject);

    function startAutoScroll() {
        autoScroll = setInterval(nextProject, 3000);
    }

    function stopAutoScroll() {
        clearInterval(autoScroll);
    }

    $('.executed-projects-wrapper').on('mouseenter', stopAutoScroll).on('mouseleave', startAutoScroll);

    startAutoScroll();

    // Adjust projectsPerView based on window width
    function adjustProjectsPerView() {
        var windowWidth = $(window).width();
        if (windowWidth < 768) {
            projectsPerView = 1;
        } else if (windowWidth < 1200) {
            projectsPerView = 2;
        } else {
            projectsPerView = 3;
        }
        updateProjects();
    }

    $(window).on('resize', adjustProjectsPerView);
    adjustProjectsPerView();
});