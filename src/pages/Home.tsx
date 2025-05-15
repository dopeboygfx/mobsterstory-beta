import { useSupabaseAuth } from '../hooks/useSupabaseAuth';

export function Home() {
  const { user, loading } = useSupabaseAuth();

  if (loading) {
    return (
      <div className="flex justify-center items-center h-[calc(100vh-4rem)]">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>
    );
  }

  if (!user) {
    return (
      <div className="max-w-4xl mx-auto text-center">
        <h1 className="text-4xl font-bold mb-8">Welcome to Mobster Story</h1>
        <p className="text-xl mb-8">
          Rise through the ranks, build your criminal empire, and become the most powerful mobster in the city.
        </p>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
          <div className="bg-gray-800 p-6 rounded-lg">
            <h3 className="text-xl font-semibold mb-4">Build Your Empire</h3>
            <p>Start from nothing and work your way to the top of the criminal underworld.</p>
          </div>
          <div className="bg-gray-800 p-6 rounded-lg">
            <h3 className="text-xl font-semibold mb-4">Join a Gang</h3>
            <p>Team up with other players to become the most powerful gang in the city.</p>
          </div>
          <div className="bg-gray-800 p-6 rounded-lg">
            <h3 className="text-xl font-semibold mb-4">Rise to Power</h3>
            <p>Complete missions, earn respect, and climb the ranks of the criminal hierarchy.</p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto">
      <div className="bg-gray-800 rounded-lg p-6 mb-8">
        <h2 className="text-2xl font-bold mb-4">Welcome back, {user.email}</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div className="bg-gray-700 p-4 rounded-lg">
            <h3 className="text-lg font-semibold mb-2">Health</h3>
            <div className="w-full bg-gray-600 rounded-full h-2.5">
              <div className="bg-green-600 h-2.5 rounded-full" style={{ width: '45%' }}></div>
            </div>
          </div>
          <div className="bg-gray-700 p-4 rounded-lg">
            <h3 className="text-lg font-semibold mb-2">Energy</h3>
            <div className="w-full bg-gray-600 rounded-full h-2.5">
              <div className="bg-blue-600 h-2.5 rounded-full" style={{ width: '70%' }}></div>
            </div>
          </div>
          <div className="bg-gray-700 p-4 rounded-lg">
            <h3 className="text-lg font-semibold mb-2">Money</h3>
            <p className="text-xl">$1,000</p>
          </div>
          <div className="bg-gray-700 p-4 rounded-lg">
            <h3 className="text-lg font-semibold mb-2">Level</h3>
            <p className="text-xl">1</p>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div className="bg-gray-800 p-6 rounded-lg">
          <h3 className="text-xl font-semibold mb-4">Available Actions</h3>
          <ul className="space-y-2">
            <li>
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                🏃‍♂️ Crime (5 nerve)
              </button>
            </li>
            <li>
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                💼 Work (10 energy)
              </button>
            </li>
            <li>
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                🏋️‍♂️ Train (5 energy)
              </button>
            </li>
          </ul>
        </div>

        <div className="bg-gray-800 p-6 rounded-lg">
          <h3 className="text-xl font-semibold mb-4">Recent Events</h3>
          <div className="space-y-4">
            <p className="text-gray-300">No recent events</p>
          </div>
        </div>
      </div>
    </div>
  );
}