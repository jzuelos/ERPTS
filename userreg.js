

// Dummy user data for demonstration (In real projects, use a backend service)
let users = [];

// Register a new user
function registerUser(username, password) {
    const existingUser = users.find(user => user.username === username);
    if (existingUser) {
        return 'Username already exists.';
    }
    users.push({ username, password });
    return 'User registered successfully.';
}

// Login a user
function loginUser(username, password) {
    const user = users.find(user => user.username === username && user.password === password);
    if (user) {
        return 'Login successful!';
    } else {
        return 'Invalid username or password.';
    }
}

// Reset password
function resetPassword(username, newPassword) {
    const user = users.find(user => user.username === username);
    if (user) {
        user.password = newPassword;
        return 'Password reset successful!';
    } else {
        return 'User not found.';
    }
}

// Export functions for use in other files
export { registerUser, loginUser, resetPassword };