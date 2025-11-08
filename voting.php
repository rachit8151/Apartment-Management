<?php
session_start();
require 'dbFile/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Assuming user is logged in
    $candidateId = $_POST['candidate_id'];

    // Check if the user has already voted
    $checkVoteQuery = "SELECT * FROM tblVotes WHERE user_id = $userId AND candidate_id = $candidateId";
    $voteResult = mysqli_query($conn, $checkVoteQuery);

    if (mysqli_num_rows($voteResult) > 0) {
        echo "You have already voted for this candidate.";
    } else {
        // Insert the vote
        $insertVoteQuery = "INSERT INTO tblVotes (user_id, candidate_id) VALUES ($userId, $candidateId)";
        if (mysqli_query($conn, $insertVoteQuery)) {
            // Update the candidate's vote count
            $updateVotesQuery = "UPDATE tblSecretaryApplications SET votes = votes + 1 WHERE id = $candidateId";
            mysqli_query($conn, $updateVotesQuery);
            echo "Your vote has been successfully cast!";
        } else {
            echo "Error: Could not vote.";
        }
    }
}

// Fetch the list of secretary applicants
$applicationsQuery = "SELECT tblSecretaryApplications.id, tblUser.name, tblSecretaryApplications.votes
                      FROM tblSecretaryApplications
                      JOIN tblUser ON tblSecretaryApplications.user_id = tblUser.user_id";
$applicationsResult = mysqli_query($conn, $applicationsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote for Secretary</title>
</head>
<body>
    <h2>Vote for Secretary</h2>
    <form method="POST">
        <label for="candidate">Choose a Candidate:</label>
        <select name="candidate_id" id="candidate">
            <?php while ($row = mysqli_fetch_assoc($applicationsResult)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> (Votes: <?php echo $row['votes']; ?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Vote</button>
    </form>
</body>
</html>
