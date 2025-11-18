<h2>Create Tour</h2>
<form method="post" action="/index.php/admin/tours/store">
    <div><label>Name <input name="tour_name" required></label></div>
    <div><label>Price <input name="price" type="number" required></label></div>
    <div><label>Type <select name="tour_type">
                <option>Trong nước</option>
                <option>Quốc tế</option>
                <option>Theo yêu cầu</option>
            </select></label></div>
    <div><label>Days <input name="duration_days" type="number" value="1"></label></div>
    <div><label>Policy <textarea name="policy"></textarea></label></div>
    <div><button>Create</button></div>
</form>