<div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>Section</th>
                <th>Notification</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Announcement</td>
                <td class="notification-cell">
                    <i class="fa fa-bell"></i>
                    <span id="noti_announcement"></span>
                </td>
            </tr>
            <tr>
                <td>Event</td>
                <td class="notification-cell">
                    <i class="fa fa-bell"></i>
                    <span id="noti_event"></span>
                </td>
            </tr>
            <tr>
                <td>Hall Booking</td>
                <td class="notification-cell">
                    <i class="fa fa-bell"></i>
                    <span id="noti_hallbooking"></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>



<script>
function loadNoti(id, el){
    setInterval(()=>{
        fetch("data.php?id="+id)
        .then(r=>r.text())
        .then(d=>document.getElementById(el).innerHTML=d);
    },1000);
}

loadNoti(1,"noti_announcement");
loadNoti(2,"noti_event");
loadNoti(3,"noti_hallbooking");
</script>
