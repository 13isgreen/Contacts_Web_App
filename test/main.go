Package main

import (
    "database/sql"
    "fmt"
    "html/template"
    "log"
    "net/http"

    _ "github.com/go-sql-driver/mysql"
)

type Contact struct {
    ID        int
    Name      string
    Email     string
    Phone     string
    CreatedAt string
}

var registerTemplate = template.Must(template.New("register").Parse(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MyApp</a>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/register">
                        <div class="mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" name="firstname" required></div>
                        <div class="mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" name="lastname" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" name="username" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div></body></html>`))

var loginTemplate = template.Must(template.New("login").Parse(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MyApp</a>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/login">
                        <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" name="username" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div></body></html>`))

var contactTemplate = template.Must(template.New("contact").Parse(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark"><div class="container-fluid"><a class="navbar-brand" href="#">MyApp</a></div></nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white"><h4 class="mb-0">Add New Contact</h4></div>
                <div class="card-body">
                    <form method="POST" action="/contacts">
                        <div class="mb-3"><label class="form-label">User ID</label><input type="number" class="form-control" name="userid" required></div>
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" name="name" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" name="phone"></div>
                        <button type="submit" class="btn btn-info w-100">Add Contact</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div></body></html>`))

var viewContactsTemplate = template.Must(template.New("viewContacts").Parse(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark"><div class="container-fluid"><a class="navbar-brand" href="#">MyApp</a></div></nav>
<div class="container mt-5">
    <h3>Contacts for User ID {{.UserID}}</h3>
    {{if .Contacts}}
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created At</th></tr></thead>
            <tbody>
            {{range .Contacts}}<tr><td>{{.ID}}</td><td>{{.Name}}</td><td>{{.Email}}</td><td>{{.Phone}}</td><td>{{.CreatedAt}}</td></tr>{{end}}
            </tbody>
        </table>
    {{else}}<p class="text-muted">No contacts found.</p>{{end}}
</div></body></html>`))

func dbConn() (*sql.DB, error) {
    dsn := "yourUser:yourPassword@tcp(127.0.0.1:3306)/UserDB"
    return sql.Open("mysql", dsn)
}

func registerHandler(w http.ResponseWriter, r *http.Request) {
    switch r.Method {
    case "GET":
        registerTemplate.Execute(w, nil)
    case "POST":
        r.ParseForm()
        firstname := r.FormValue("firstname")
        lastname := r.FormValue("lastname")
        email := r.FormValue("email")
        username := r.FormValue("username")
        password := r.FormValue("password")

        db, err := dbConn()
        if err != nil { http.Error(w, "DB error", 500); return }
        defer db.Close()

        stmt, err := db.Prepare(`INSERT INTO Users(FirstName, LastName, Email, Username, Password) VALUES(?,?,?,?,?)`)
        if err != nil { http.Error(w, "Query error", 500); return }

        _, err = stmt.Exec(firstname, lastname, email, username, password)
        if err != nil { http.Error(w, "Insert failed: "+err.Error(), 500); return }

        http.Redirect(w, r, "/login", http.StatusSeeOther)
    }
}

func loginHandler(w http.ResponseWriter, r *http.Request) {
    switch r.Method {
    case "GET":
        loginTemplate.Execute(w, nil)
    case "POST":
        r.ParseForm()
        username := r.FormValue("username")
        password := r.FormValue("password")

        db, err := dbConn()
        if err != nil { http.Error(w, "DB error", 500); return }
        defer db.Close()

        var dbPassword string
        err = db.QueryRow("SELECT Password FROM Users WHERE Username = ?", username).Scan(&dbPassword)
        if err != nil || password != dbPassword {
            http.Error(w, "Invalid credentials", 401)
            return
        }

        fmt.Fprintf(w, "Welcome, %s! Login successful.", username)
    }
}

func contactsHandler(w http.ResponseWriter, r *http.Request) {
    switch r.Method {
    case "GET":
        contactTemplate.Execute(w, nil)
    case "POST":
        r.ParseForm()
        userID := r.FormValue("userid")
        name := r.FormValue("name")
        email := r.FormValue("email")
        phone := r.FormValue("phone")

        db, err := dbConn()
        if err != nil { http.Error(w, "DB error", 500); return }
        defer db.Close()

        stmt, err := db.Prepare(`INSERT INTO Contacts(UserID, Name, Email, Phone) VALUES(?,?,?,?)`)
        if err != nil { http.Error(w, "Prepare failed", 500); return }

        _, err = stmt.Exec(userID, name, email, phone)
        if err != nil { http.Error(w, "Insert failed: "+err.Error(), 500); return }

        fmt.Fprintf(w, "Contact %s added successfully!", name)
    }
}

func viewContactsHandler(w http.ResponseWriter, r *http.Request) {
    userID := r.URL.Query().Get("userid")
    if userID == "" {
        http.Error(w, "Missing userid", 400)
        return
    }

    db, err := dbConn()
    if err != nil { http.Error(w, "DB error", 500); return }
    defer db.Close()

    rows, err := db.Query(`SELECT ID, Name, Email, Phone, CreatedAt FROM Contacts WHERE UserID = ?`, userID)
    if err != nil { http.Error(w, "Query failed", 500); return }
    defer rows.Close()

    var contacts []Contact
    for rows.Next() {
        var c Contact
        rows.Scan(&c.ID, &c.Name, &c.Email, &c.Phone, &c.CreatedAt)
        contacts = append(contacts, c)
    }

    data := struct {
        UserID   string
        Contacts []Contact
    }{userID, contacts}

    viewContactsTemplate.Execute(w, data)
}

func main() {
    http.HandleFunc("/register", registerHandler)
    http.HandleFunc("/login", loginHandler)
    http.HandleFunc("/contacts", contactsHandler)
    http.HandleFunc("/contacts/view", viewContactsHandler)

    fmt.Println("Server running at http://localhost:8080")
    log.Fatal(http.ListenAndServe(":8080", nil))
}

