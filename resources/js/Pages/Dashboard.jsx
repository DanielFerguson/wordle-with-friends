import { useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-react";

const BoxCard = ({ children }) => {
    return (
        <div className="col-span-1 bg-gray-200 rounded shadow flex items-center justify-center h-64 transition transform">
            {children}
        </div>
    );
};

const Dashboard = ({ groups = [] }) => {
    const [values, setValues] = useState({
        code: "",
    });

    function handleChange(e) {
        const key = e.target.id;
        const value = e.target.value;
        setValues((values) => ({
            ...values,
            [key]: value,
        }));
    }

    function handleSubmit(e) {
        e.preventDefault();
        Inertia.post("/groups/join", values);
        axios.post("/groups/join", { code: values.code });
    }

    return (
        <div className="flex flex-col justify-between min-h-screen max-w-6xl mx-auto py-6 px-8">
            <header className="flex items-center justify-between">
                <img
                    src="/logo.png"
                    className="h-16 w-auto"
                    alt="Wordle with Friends"
                />

                <nav>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        type="button"
                    >
                        Logout
                    </Link>
                </nav>
            </header>

            <main className="max-w-2xl mx-auto w-full">
                {/* Groups */}
                <div>
                    <h2>Groups</h2>
                    <div className="flex flex-col gap-2 mt-4 border">
                        {groups &&
                            groups.map((group) => (
                                <div className="flex justify-between p-4">
                                    <div>{group.id}</div>
                                    <div>{group.join_code}</div>
                                    <div>{group.type}</div>
                                    <div>
                                        <a href={`/groups/${group.id}/play`}>
                                            Play
                                        </a>
                                        <a href={`/groups/${group.id}/play`}>
                                            Invite
                                        </a>
                                    </div>
                                </div>
                            ))}
                    </div>
                </div>

                {/* Actions */}
                <div className="grid grid-cols-2 gap-8 mt-8">
                    <BoxCard>
                        <a href="/play">Start a game</a>
                    </BoxCard>
                    <BoxCard>Something</BoxCard>
                    <BoxCard>
                        <a href="/groups/create">Create a Group</a>
                    </BoxCard>
                    <BoxCard>
                        <div className="flex flex-col gap-2 items-center">
                            <div>Join a Group</div>
                            <form
                                onSubmit={handleSubmit}
                                className="flex gap-2"
                            >
                                <input
                                    id="code"
                                    value={values.code}
                                    placeholder="abc123"
                                    className="flex-grow"
                                    onChange={handleChange}
                                />
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </BoxCard>
                </div>
            </main>

            <footer className="flex justify-end">
                <div className="text-gray-400">
                    Made with ❤️ by{" "}
                    <a
                        href="https://twitter.com/thedannyferg"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        @thedannyferg
                    </a>
                </div>
            </footer>
        </div>
    );
};

export default Dashboard;
``;
