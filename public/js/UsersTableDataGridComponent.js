window.addEventListener("DOMContentLoaded", () => {
    const addTableRow = (data) => {
        const tableBody = document.getElementById("users-table-body");
        if (tableBody === null) {
            return
        }

        const row = document.createElement("tr");
        row.innerHTML = `
      <th scope="row" class="align-middle"><img src="${data.profilePic}" class="rounded-circle" style="width: 40px;" alt="avatar"></th>
      <td class="align-middle">${data.name}</td>
      <td class="align-middle">${data.age}</td>
      <td class="align-middle" style="padding-left: 0">
        <img alt="flag" src="${GetFlagURL(data.country)}"> ${data.country}
      </td>
      <td class="align-middle">${data.email}</td>
    `;

        tableBody.appendChild(row);
    };

    const clearTableRows = () => {
        const tableBody = document.getElementById("users-table-body");
        while (tableBody.firstChild) {
            tableBody.removeChild(tableBody.firstChild);
        }
    }

    const inputField = document.getElementById('filterInput');
    const filterButton = document.getElementById('filterButton');
    filterButton.addEventListener('click', function () {
        renderUserTableContent(inputField.value)
    });

    const renderUserTableContent = (name = null) => {
        let url = "/users";
        if (name) {
            url = `/users?name=${name}`
        }

        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Can't take data about users, please try later: response status ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                clearTableRows();
                data.forEach((user, index) => {
                    addTableRow(user, index);
                });
            })
            .catch((error) => {
                console.error("Fetch error:", error);
                alert(error)
            });
    }

    renderUserTableContent()
})