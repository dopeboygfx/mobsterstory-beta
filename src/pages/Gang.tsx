import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { useSupabaseAuth } from '../hooks/useSupabaseAuth';
import toast from 'react-hot-toast';

interface GangData {
  id: string;
  name: string;
  tag: string;
  leader_id: string;
  money: number;
  level: number;
  exp: number;
  created_at: string;
}

interface GangMember {
  id: string;
  username: string;
  level: number;
}

export function Gang() {
  const { id } = useParams();
  const { user } = useSupabaseAuth();
  const navigate = useNavigate();
  const [gang, setGang] = useState<GangData | null>(null);
  const [members, setMembers] = useState<GangMember[]>([]);
  const [loading, setLoading] = useState(true);
  const [isLeader, setIsLeader] = useState(false);

  useEffect(() => {
    if (!user) {
      navigate('/auth');
      return;
    }
    
    fetchGangData();
  }, [id, user, navigate]);

  async function fetchGangData() {
    try {
      setLoading(true);
      
      if (!id) {
        // Check if user is in a gang
        const { data: userData, error: userError } = await supabase
          .from('users')
          .select('gang_id')
          .eq('id', user?.id)
          .single();

        if (userError) throw userError;
        
        if (userData.gang_id) {
          navigate(`/gang/${userData.gang_id}`);
          return;
        } else {
          // User is not in a gang
          setLoading(false);
          return;
        }
      }

      // Fetch gang data
      const { data: gangData, error: gangError } = await supabase
        .from('gangs')
        .select('*')
        .eq('id', id)
        .single();

      if (gangError) throw gangError;
      setGang(gangData);
      setIsLeader(gangData.leader_id === user?.id);

      // Fetch gang members
      const { data: membersData, error: membersError } = await supabase
        .from('users')
        .select('id, username, level')
        .eq('gang_id', id);

      if (membersError) throw membersError;
      setMembers(membersData);
    } catch (error) {
      toast.error('Error loading gang data');
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  }

  if (loading) {
    return (
      <div className="flex justify-center items-center h-[calc(100vh-4rem)]">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>
    );
  }

  if (!id && !gang) {
    return (
      <div className="max-w-4xl mx-auto">
        <div className="bg-gray-800 rounded-lg p-6 mb-8">
          <h2 className="text-2xl font-bold mb-4">You're not in a gang</h2>
          <p className="mb-6">Join an existing gang or create your own to access gang features.</p>
          
          <div className="flex space-x-4">
            <button 
              className="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md"
              onClick={() => navigate('/gangs')}
            >
              Browse Gangs
            </button>
            <button 
              className="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md"
              onClick={() => navigate('/create-gang')}
            >
              Create Gang
            </button>
          </div>
        </div>
      </div>
    );
  }

  if (!gang) {
    return (
      <div className="text-center mt-8">
        <h2 className="text-2xl font-bold">Gang not found</h2>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto">
      <div className="bg-gray-800 rounded-lg p-6 mb-8">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h2 className="text-3xl font-bold">[{gang.tag}] {gang.name}</h2>
            <p className="text-gray-400">Level {gang.level} Gang</p>
          </div>
          
          {isLeader && (
            <button className="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md">
              Manage Gang
            </button>
          )}
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 className="text-xl font-semibold mb-3">Gang Stats</h3>
            <div className="bg-gray-700 p-4 rounded-lg space-y-2">
              <div className="flex justify-between">
                <span>Level:</span>
                <span>{gang.level}</span>
              </div>
              <div className="flex justify-between">
                <span>Experience:</span>
                <span>{gang.exp.toLocaleString()}</span>
              </div>
              <div className="flex justify-between">
                <span>Treasury:</span>
                <span>${gang.money.toLocaleString()}</span>
              </div>
              <div className="flex justify-between">
                <span>Members:</span>
                <span>{members.length}</span>
              </div>
              <div className="flex justify-between">
                <span>Founded:</span>
                <span>{new Date(gang.created_at).toLocaleDateString()}</span>
              </div>
            </div>
          </div>
          
          <div>
            <h3 className="text-xl font-semibold mb-3">Gang Actions</h3>
            <div className="space-y-2">
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                Gang Crimes
              </button>
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                Gang Vault
              </button>
              <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                Gang Armory
              </button>
              {isLeader && (
                <button className="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">
                  Manage Members
                </button>
              )}
            </div>
          </div>
        </div>
      </div>
      
      <div className="bg-gray-800 rounded-lg p-6">
        <h3 className="text-xl font-semibold mb-4">Members</h3>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="text-left border-b border-gray-700">
                <th className="pb-2">Username</th>
                <th className="pb-2">Level</th>
                <th className="pb-2">Role</th>
                {isLeader && <th className="pb-2">Actions</th>}
              </tr>
            </thead>
            <tbody>
              {members.map((member) => (
                <tr key={member.id} className="border-b border-gray-700">
                  <td className="py-3">
                    <a 
                      href={`/profile/${member.id}`}
                      className="text-blue-400 hover:text-blue-300"
                    >
                      {member.username}
                    </a>
                  </td>
                  <td className="py-3">{member.level}</td>
                  <td className="py-3">
                    {member.id === gang.leader_id ? 'Leader' : 'Member'}
                  </td>
                  {isLeader && member.id !== user?.id && (
                    <td className="py-3">
                      <button className="text-red-400 hover:text-red-300">
                        Kick
                      </button>
                    </td>
                  )}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}