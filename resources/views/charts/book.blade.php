<div class="chart-container">
    <h1>Highest Book Borrowed</h1>
    <div class="filter-container">
        <label for="interval">Select Interval: </label>
        <select id="interval">
            <option value="yearly" selected>Yearly</option>
            <option value="monthly">Monthly</option>
            <option value="weekly">Weekly</option> <!-- Default is 'Yearly' -->
        </select>
    </div>
    <canvas id="borrowedChart"></canvas>
    <div id="highestBorrowedBook" style="margin-top: 20px;"></div>
</div>
