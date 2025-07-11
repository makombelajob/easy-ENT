function calendar(){
    window.onload = () => {
        let calendarElt = document.querySelector('#calendar');

        let calendar = new FullCalendar.Calendar(calendarElt, {
            initialView: 'dayGridMonth',
            locale: 'fr', 
            timeZone: 'Europe/Paris',
            headerToolbar: {
                start: 'prev,next today',
                center: 'title',
                end: 'dayGridMonth,timeGridWeek'
            },
            events : window.horaires
        });

        calendar.render()
    }
}

calendar();