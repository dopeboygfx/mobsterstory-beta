import { Link } from 'react-router-dom';
import { useSupabaseAuth } from '../hooks/useSupabaseAuth';

export function Navbar() {
  const { user, signOut } = useSupabaseAuth();

  return (
    <nav className="bg-gray-800 shadow-lg">
      <div className="max-w-7xl mx-auto px-4">
        <div className="flex justify-between h-16">
          <div className="flex items-center">
            <Link to="/" className="text-xl font-bold text-white">
              Mobster Story
            </Link>
            
            <div className="hidden md:flex items-center space-x-4 ml-10">
              <Link to="/" className="text-gray-300 hover:text-white px-3 py-2">
                Home
              </Link>
              {user && (
                <>
                  <Link to="/bank" className="text-gray-300 hover:text-white px-3 py-2">
                    Bank
                  </Link>
                  <Link to={`/profile/${user.id}`} className="text-gray-300 hover:text-white px-3 py-2">
                    Profile
                  </Link>
                  <Link to="/gang" className="text-gray-300 hover:text-white px-3 py-2">
                    Gang
                  </Link>
                </>
              )}
            </div>
          </div>

          <div className="flex items-center">
            {user ? (
              <button
                onClick={() => signOut()}
                className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
              >
                Sign Out
              </button>
            ) : (
              <Link
                to="/login"
                className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md"
              >
                Sign In
              </Link>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}