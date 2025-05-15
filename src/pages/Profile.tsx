import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { useSupabaseAuth } from '../hooks/useSupabaseAuth';
import toast from 'react-hot-toast';

interface UserProfile {
  username: string;
  money: number;
  bank_money: number;
  health: number;
  max_health: number;
  energy: number;
  max_energy: number;
  nerve: number;
  max_nerve: number;
  level: number;
  exp: number;
}

export function Profile() {
  const { id } = useParams();
  const { user } = useSupabaseAuth();
  const [profile, setProfile] = useState<UserProfile | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchProfile() {
      try {
        const { data, error } = await supabase
          .from('users')
          .select('*')
          .eq('id', id)
          .single();

        if (error) throw error;
        setProfile(data);
      } catch (error) {
        toast.error('Error loading profile');
        console.error('Error:', error);
      } finally {
        setLoading(false);
      }
    }

    fetchProfile();
  }, [id]);

  if (loading) {
    return (
      <div className="flex justify-center items-center h-[calc(100vh-4rem)]">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>
    );
  }

  if (!profile) {
    return (
      <div className="text-center mt-8">
        <h2 className="text-2xl font-bold">Profile not found</h2>
      </div>
    );
  }

  const isOwnProfile = user?.id === id;

  return (
    <div className="max-w-4xl mx-auto">
      <div className="bg-gray-800 rounded-lg p-6 mb-8">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-2xl font-bold">{profile.username}'s Profile</h2>
          {isOwnProfile && (
            <button className="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">
              Edit Profile
            </button>
          )}
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="space-y-4">
            <div>
              <h3 className="text-lg font-semibold mb-2">Stats</h3>
              <div className="space-y-2">
                <div>
                  <div className="flex justify-between mb-1">
                    <span>Health</span>
                    <span>{profile.health}/{profile.max_health}</span>
                  </div>
                  <div className="w-full bg-gray-700 rounded-full h-2">
                    <div 
                      className="bg-green-600 h-2 rounded-full" 
                      style={{ width: `${(profile.health / profile.max_health) * 100}%` }}
                    />
                  </div>
                </div>

                <div>
                  <div className="flex justify-between mb-1">
                    <span>Energy</span>
                    <span>{profile.energy}/{profile.max_energy}</span>
                  </div>
                  <div className="w-full bg-gray-700 rounded-full h-2">
                    <div 
                      className="bg-blue-600 h-2 rounded-full" 
                      style={{ width: `${(profile.energy / profile.max_energy) * 100}%` }}
                    />
                  </div>
                </div>

                <div>
                  <div className="flex justify-between mb-1">
                    <span>Nerve</span>
                    <span>{profile.nerve}/{profile.max_nerve}</span>
                  </div>
                  <div className="w-full bg-gray-700 rounded-full h-2">
                    <div 
                      className="bg-red-600 h-2 rounded-full" 
                      style={{ width: `${(profile.nerve / profile.max_nerve) * 100}%` }}
                    />
                  </div>
                </div>
              </div>
            </div>

            <div>
              <h3 className="text-lg font-semibold mb-2">Level & Experience</h3>
              <p className="text-xl mb-2">Level {profile.level}</p>
              <div className="w-full bg-gray-700 rounded-full h-2">
                <div 
                  className="bg-yellow-600 h-2 rounded-full" 
                  style={{ width: '45%' }}
                />
              </div>
            </div>
          </div>

          <div className="space-y-4">
            <div>
              <h3 className="text-lg font-semibold mb-2">Finances</h3>
              <div className="bg-gray-700 p-4 rounded-lg space-y-2">
                <div className="flex justify-between">
                  <span>Cash:</span>
                  <span>${profile.money.toLocaleString()}</span>
                </div>
                <div className="flex justify-between">
                  <span>Bank:</span>
                  <span>${profile.bank_money.toLocaleString()}</span>
                </div>
                <div className="flex justify-between font-semibold">
                  <span>Total:</span>
                  <span>${(profile.money + profile.bank_money).toLocaleString()}</span>
                </div>
              </div>
            </div>

            {isOwnProfile && (
              <div>
                <h3 className="text-lg font-semibold mb-2">Quick Actions</h3>
                <div className="space-y-2">
                  <button className="w-full bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
                    Deposit Money
                  </button>
                  <button className="w-full bg-red-600 hover:bg-red-700 px-4 py-2 rounded">
                    Withdraw Money
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}