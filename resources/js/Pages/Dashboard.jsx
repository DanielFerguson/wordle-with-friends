import { Link } from "@inertiajs/inertia-react";

const Dashboard = () => {
    return (
        <>
            <p>Here is some basic text. Much wow, little shit.</p>
            <Link href="/logout" method="post" as="button" type="button">
                Logout
            </Link>
        </>
    );
};

export default Dashboard;
