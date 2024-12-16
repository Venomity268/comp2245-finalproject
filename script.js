document.addEventListener("DOMContentLoaded", function () {
    const allTicketsBtn = document.getElementById("all-tickets");
    const openTicketsBtn = document.getElementById("open-tickets");
    const myTicketsBtn = document.getElementById("my-tickets");
    const tableBody = document.getElementById("issues-table-body");

    function fetchIssues(filter) {
        fetch(`get_issues.php?filter=${filter}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Failed to fetch issues");
                }
                return response.json();
            })
            .then((issues) => {
                tableBody.innerHTML = "";

                issues.forEach((issue) => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${issue.id}</td>
                        <td><a href="view_issue.php?id=${issue.id}">${issue.title}</a></td>
                        <td>${issue.type}</td>
                        <td>${issue.status}</td>
                        <td>${issue.firstname} ${issue.lastname}</td>
                        <td>${issue.created_at}</td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch((error) => {
                console.error(error);
                tableBody.innerHTML = "<tr><td colspan='6'>Error loading issues</td></tr>";
            });
    }

    allTicketsBtn.addEventListener("click", () => fetchIssues("all"));
    openTicketsBtn.addEventListener("click", () => fetchIssues("open"));
    myTicketsBtn.addEventListener("click", () => fetchIssues("my"));

    fetchIssues("all");
});

