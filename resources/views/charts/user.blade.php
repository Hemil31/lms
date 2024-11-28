<div class="chart-container">
    <h1>Top User Activity</h1>
    <div class="filter-container">
        <label for="user-activity-interval">Select Interval: </label>
        <select id="user-activity-interval">
            <option value="yearly" selected>Yearly</option>
            <option value="monthly">Monthly</option>
            <option value="weekly">Weekly</option>
        </select>
    </div>
    <canvas id="userActivityChart"></canvas>
    <div id="topUserDetails" style="margin-top: 20px;"></div>
</div>
